<?php

namespace App\Controller\Admin\Meta;

use App\Entity\Meta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MetaController extends AbstractController
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/meta/packages", name="admin_meta_packages")
     */
    public function packages(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Meta::class);

        $packages_rep = $repository->findOneBy(['type' => 'packages']);

        if ($packages_rep)
            $packages = json_decode($packages_rep->getValue(), true);
        else
            $packages = [];

        if ($request->isMethod('GET')) {
            return $this->render('admin/meta/Packages.html.twig', ['packages' => $packages]);
        }

        if (($packages)) {
            $packages_rep->setValue(json_encode($_POST));
            $em->flush();
        } else {
            $package = new Meta();
            $package->setType('packages');
            $package->setCreatedAt();
            $package->setValue(json_encode($_POST));
            $em->persist($package);
            $em->flush();
        }
        return $this->redirectToRoute('admin_meta_packages');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/meta/packages/custom", name="admin_meta_custom_packages")
     */
    public function customPackages(Request $request){

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Meta::class);


        $packages = $repository->findOneBy(['type' => 'custom_packages']);

        if($request->isMethod('GET')){
            if($packages)
                $packages = json_decode($packages->getValue(),true);
            return $this->render('admin/meta/CustomPackages.html.twig',['packages' => $packages]);
        }

        if($packages){
            $packages->setValue(json_encode($_POST));
            $em->flush();
        }else{
            $package = new Meta();
            $package->setType('custom_packages');
            $package->setCreatedAt();
            $package->setValue(json_encode($_POST));
            $em->persist($package);
            $em->flush();
        }

        return $this->redirectToRoute('admin_meta_custom_packages');
}


}
