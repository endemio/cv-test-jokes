<?php


namespace App\Controller;

use App\Form\FormType;
use App\Service\EmailService;
use App\Service\ICBDB\CategoryService;
use App\Service\ICBDB\RandomJoke;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{

    private $category;

    private $joke;

    private $email;

    /**
     * PageController constructor.
     * @param CategoryService $categoryServiceICBDB
     * @param RandomJoke $randomJokeICBDB
     * @param EmailService $emailService
     */
    public function __construct(
        CategoryService $categoryServiceICBDB,
        RandomJoke $randomJokeICBDB,
        EmailService $emailService)
    {
        $this->category = $categoryServiceICBDB;

        $this->joke = $randomJokeICBDB;

        $this->email = $emailService;
    }

    /**
     * @Route("/", name="index", methods={"POST","GET"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request):Response
    {

        $error = [];
        $email_sent = false;

        try {
            $categories = $this->category->getCategoriesList();
        } catch (\Exception $exception) {
            array_push($error,$exception->getMessage());
            $categories = [];
        }

        if ($request->getMethod()=='POST'){

            $form = $this->createForm(FormType::class,[],[
                'csrf_protection' => false,
            ]);
            $form->submit($request->request->all());

            if (!$form->isValid()) {
                throw new HttpException(400,$form->getErrors());
            }

            if (!$this->isCsrfTokenValid('confirm_form',$request->request->get('_confirm_token'))){
                $this->redirect('/');
            }

            try{
                $category = $request->request->get('category');
            } catch (\Exception $exception){
                array_push($error,$exception->getMessage());
                $category = null;
            }

            $joke_array = [];
            if ($category) {
                try {
                    $joke_array = $this->joke->getJoke([$category]);
                } catch (\Exception $exception) {
                    array_push($error, $exception->getMessage());
                }
            }

            if (isset($joke_array['joke'])){
                try {

                    file_put_contents($_ENV['PATH_JOKE_STORE'].'/joke.txt', $joke_array['joke']);

                    $this->email->sendEmail(
                        'email.html.twig',
                        'error@endemic.ru',
                        [$form->get('email')->getData()],
                        sprintf($_ENV['EMAIL_SUBJECT'],$category),
                        ['joke' => $joke_array['joke']]
                    );

                    $email_sent = true;

                }catch (\Exception $exception){
                    array_push($error, $exception->getMessage());
                }
            }
        }

        return $this->render('index.html.twig', [
            'error' => $error,
            'email_sent' => $email_sent,
            'categories' => $categories,
        ]);

    }

}