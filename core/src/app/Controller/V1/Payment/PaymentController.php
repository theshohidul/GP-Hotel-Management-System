<?php


namespace App\Controller\V1\Payment;


use App\DTO\Customer\CustomerCreateDTO;
use App\Service\Payment\PaymentService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class PaymentController
{
    /**
     * @var
     */
    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }


    public function makePayment(Request $request, Response $response, $bookingId)
    {

        $paymentData = $request->getParsedBody();

        $payment = $this->paymentService->addNew($bookingId, $paymentData);

        $statusCode = 200;

        if ($payment)
        {
            $resp = [
                'status'  => $statusCode,
                'message' => 'Payment Done Successfully!'
            ];
        }else
        {
            $resp = [
                'status'  => $statusCode,
                'message' => 'Could not processed your payment! Please contact administrator!'
            ];
            $statusCode = 422;
        }

        $resp = json_encode($resp, JSON_PRETTY_PRINT);
        $response =  $response->withHeader('Content-type', 'application/json');
        $response =  $response->withStatus($statusCode);
        $response->getBody()->write($resp);
        return $response;

    }
}