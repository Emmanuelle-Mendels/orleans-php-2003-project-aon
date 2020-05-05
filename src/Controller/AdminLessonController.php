<?php

namespace App\Controller;

use App\Model\ActivityManager;
use App\Model\AgeManager;
use App\Model\LessonManager;
use App\Model\PoolManager;

class AdminLessonController extends AbstractController
{

    public function createLesson(string $message = "")
    {
        $message = urldecode($message);
        return $this->twig->render('Admin/addLesson.html.twig', ['message' => $message]);
    }

    public function addLesson()
    {
        $activityManager = new ActivityManager();
        $activities = $activityManager->selectAll();
        $ageManager = new AgeManager();
        $ages = $ageManager->selectAll();
        $poolManager = new PoolManager();
        $pools = $poolManager->selectAll();


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lesson = array_map('trim', $_POST);
            return var_dump($lesson);
            $errors = $this->validation($lesson);

            if (empty($errors)) {
                $lessonManager = new LessonManager();
                $lessonManager->insert($lesson);
                header("location:/admin/index");
            }
        }
        return $this->twig->render('Admin/addLesson.html.twig', [
            'errors' => $errors ?? [],
            'lesson' => $lesson ?? [],
            'activities' => $activities ?? [],
            'ages' => $ages ?? [],
            'pools' => $pools ?? [],
            'id' => $id ?? [],
        ]);
    }

    private function validation(array $lesson) : array
    {
        $errors = [];
        if (empty($lesson['day'])) {
            $errors[] = 'Le jour doit être indiqué';
        } elseif (!in_array($lesson['day'], ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'])) {
            $errors[] = 'Le jour doit être dans la liste';
        }
        if (empty($lesson['time'])) {
            $errors[] = 'L\'heure doit être indiquée';
        } elseif (!preg_match(
            '^([0-1]?[0-9]|2[0-3])h[0-5][0-9]-([0-1]?[0-9]|2[0-3])h[0-5][0-9]^',
            $lesson['time']
        )) {
            $errors[] = 'Format de l\'heure 20h00-21h30';
        }
        if (empty($lesson['price'])) {
            $errors[] = 'Le prix doit être indiqué';
        } elseif (!is_numeric($lesson['price'])) {
            $errors[] = 'Le prix doit être indiqué en chiffres';
        } elseif ($lesson['price'] < 0) {
            $errors[] = 'Le prix doit être supérieur à 0';
        }

        return $errors ?? [];
    }

    /**
     * Display event informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showLesson(int $id)
    {
        $lessonManager = new LessonManager();
        $lesson = $lessonManager->selectOneById($id);
        return $this->twig->render('Admin/editLesson.html.twig', ['lesson' => $lesson]);
    }

    public function editLesson()
    {
        $activityManager = new ActivityManager();
        $activities = $activityManager->selectAll();
        $ageManager = new AgeManager();
        $ages = $ageManager->selectAll();
        $poolManager = new PoolManager();
        $pools = $poolManager->selectAll();


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lesson = array_map('trim', $_POST);

            $errors = $this->validation($lesson);

            if (empty($errors)) {
                $lessonManager = new LessonManager();
                $lessonManager->editLesson($lesson);
                header("location:/admin/index");
            }
        }

        return $this->twig->render('Admin/editLesson.html.twig', [
            'errors' => $errors ?? [],
            'lesson' => $lesson ?? [],
            'activities' => $activities ?? [],
            'ages' => $ages ?? [],
            'pools' => $pools ?? [],
            'id' => $id ?? [],
        ]);
    }
}
