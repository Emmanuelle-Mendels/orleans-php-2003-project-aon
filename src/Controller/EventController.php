<?php
/**
 * Created by PhpStorm.
 * User: Emmanuelle
 * Date: 15/04/2020
 */

namespace App\Controller;

use App\Model\EventManager;

class EventController extends AbstractController
{
    /**
     * Display event page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $eventManager = new EventManager();
        $events=$eventManager->selectAll();

        return $this->twig->render('Event/index.html.twig', ['events'=>$events,]);
    }
}
