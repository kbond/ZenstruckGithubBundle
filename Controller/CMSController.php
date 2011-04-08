<?php

namespace Zenstruck\GithubCMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CMSController extends Controller
{
    public function showAction()
    {
        $file = $this->get('github.cms.manager')->getFile('about');

        switch (pathinfo($file['name'], PATHINFO_EXTENSION)) {
            case 'md':
            case 'markdown':
            case 'mdown':
                $format = 'markdown';
                break;

            case 'html':
            default:
                $format = 'html';
                break;
        }

        return $this->render('ZenstruckGithubCMSBundle:CMS:show.html.twig', array('content' => $file['data'], 'format' => $format));
    }
}
