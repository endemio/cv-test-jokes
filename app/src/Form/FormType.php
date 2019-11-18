<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class FormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('email', TextType::class,[
                'constraints' => [
                    new NotBlank(),
                    new Email([
                        'message' => 'The email \'{{ value }}\' is not a valid email',
                    ]),
                ]])
            ->add('category', TextType::class,[
                'constraints' => [
                    new NotBlank()
                ]])
            ->add('_confirm_token', TextType::class,[
                'constraints' => [
                    new NotBlank()
                ]])
        ;
    }
}