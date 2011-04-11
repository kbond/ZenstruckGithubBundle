<?php

namespace Zenstruck\GithubCMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CMSController extends Controller
{
    public function showAction($path)
    {
        $file = $this->get('zenstruck.github.filesystem')->getMatchingFile($path);

        switch (pathinfo($file->getPath(), PATHINFO_EXTENSION)) {
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

        $this->generateUrl('github.cms.homepage');

        return $this->render('ZenstruckGithubCMSBundle:CMS:show.html.twig', array('content' => $file, 'format' => $format));
    }
}
