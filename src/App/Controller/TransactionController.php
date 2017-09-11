<?php

namespace Bicycle\App\Controller;

use Bicycle\App\Entity\Transaction;
use Bicycle\App\Repository\TransactionRepository;
use Bicycle\App\Service\TransactionService;
use Bicycle\Core\Application;
use Bicycle\Core\Controller;
use Bicycle\Core\EntityValidator;
use Bicycle\Core\Exception\AuthorizationException;
use Bicycle\Core\Request;
use Bicycle\Core\Route;

class TransactionController extends Controller
{
    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     * @var TransactionService
     */
    private $transactionService;

    /**
     * @var EntityValidator
     */
    private $entityValidator;

    /**
     * @var string
     */
    private $authBasicToken;

    public function __construct(
        TransactionRepository $transactionRepository,
        TransactionService $transactionService,
        EntityValidator $entityValidator,
        string $authBasicToken
    )
    {
        $this->transactionRepository = $transactionRepository;
        $this->transactionService = $transactionService;
        $this->entityValidator = $entityValidator;
        $this->authBasicToken = $authBasicToken;
    }

    /**
     * Processes transaction
     *
     * @param Request $request
     * @param Route $route
     * @return array|string
     * @throws AuthorizationException
     */
    public function processTransaction(Request $request, Route $route): array
    {
        $requestBody = $request->getBody();

        if (!$this->isJson($requestBody)) {
            throw new \InvalidArgumentException('JSON request body required.');
        }

        $requestData = json_decode($requestBody, true);
        if (!isset($requestData['amount'])) {
            throw new \InvalidArgumentException('Amount field is required in request.');
        }

        if (!is_numeric($requestData['amount'])) {
            throw new \InvalidArgumentException('Amount field value should be numeric.');
        }

        if (!$this->checkBasicAuthToken($request, $this->authBasicToken)) {
            throw new AuthorizationException('Authorization token is incorrect.');
        }

        $uriParams = $route->getUriParams();
        $email = $uriParams['email'];
        $amount = $requestData['amount'];

        $transaction = new Transaction();
        $transaction
            ->setAmount($amount)
            ->setEmail($email);

        $this->transactionService->processTransaction($transaction);

        $errorMessages = [];
        if ($transaction->getStatus() == Transaction::STATUS_REJECTED) {
            $errorMessages[] = 'Transaction rejected. Fraud detected.';
        }

        $validationErrorMessages = $this->entityValidator->validate($transaction);

        if (!$validationErrorMessages) {
            $transaction = $this->transactionRepository->save($transaction);
        }

        $errorMessages = array_merge($errorMessages, $validationErrorMessages);

        return [
            'status' => $validationErrorMessages ? Application::HTTP_STATUS_BAD_REQUEST : Application::HTTP_STATUS_OK,
            'transaction' => $transaction,
            'errorMessages' => $errorMessages,
        ];
    }
}