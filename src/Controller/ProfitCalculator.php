<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Buy;
use App\Entity\Sale;
use App\Form\Type\ActionType;
use App\Service\CalculatorService;
use Symfony\Component\Config\Definition\Exception\Exception;

class ProfitCalculator extends AbstractController
{
    private $calculator;

    public function __construct(CalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    public function actionCalculator(Request $request)
    {
        $buy = new Buy();
        $form = $this->createForm(ActionType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $this->calculator->handleAction($data['action'], $data);
                $this->addFlash('success', 'Submitted successfully.');
                return $this->redirectToRoute('profit_calculator');
            } catch (Exception $e) {
                $this->addFlash('error', 'Error: '.$e->getMessage());
                return $this->redirectToRoute('profit_calculator');
            }
        }
        
        $profit = $this->calculator->getProfit();
        return $this->render('profit_calculator/index.html.twig', [
            'profit' => $profit,
            'form' => $form->createView(),
        ]);
    }
}
