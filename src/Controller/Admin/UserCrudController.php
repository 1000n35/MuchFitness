<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Utilisateurs')
            ->setEntityLabelInSingular('Utilisateur')
            ->setPageTitle("index", "Administration des utilisateurs");
    }


    public function configureFields(string $pageName): iterable
    {
        if(in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            return [
                IdField::new('id')
                    ->hideOnForm(),
                TextField::new('Username'),
                ArrayField::new('roles'),
                BooleanField::new('coach'),
                TextField::new('password')
                    ->hideOnIndex()
                    ->hideOnForm(),
                TextField::new('email')
                    ->hideOnForm(),
                TextField::new('nom'),
                TextField::new('prenom'),
                DateTimeField::new('date_naissance')
                    ->setFormTypeOption('disabled', 'disabled'),
                TextField::new('telephone')
                    ->hideOnForm(),
            ];
        }
        else {
            return [
                IdField::new('id')
                    ->hideOnForm(),
                TextField::new('Username'),
                ArrayField::new('roles')
                    ->hideOnForm(),
                BooleanField::new('coach'),
                TextField::new('password')
                    ->hideOnIndex()
                    ->hideOnForm(),
                TextField::new('email')
                    ->hideOnForm(),
                TextField::new('nom'),
                TextField::new('prenom'),
                DateTimeField::new('date_naissance')
                    ->setFormTypeOption('disabled', 'disabled'),
                TextField::new('telephone')
                    ->hideOnForm(), 
            ];
        }
    }
}
