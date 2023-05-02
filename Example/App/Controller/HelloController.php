<?php

declare(strict_types=1);

namespace Example\App\Controller;

use Phprise\Routing\Route;
use Symfony\Component\HttpFoundation\Request;

class HelloController
{
    /**
     * Simple route example
     *
     * @return void
     */
    #[Route('/')]
    public function index()
    {
        $title      =   'Hello World';
        $text       =   'This is an example';

        echo sprintf('<h1>%s</h1><p>%s</p>', $title, $text);
    }


    /**
     * Routing with parameters
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return void
     */
    #[Route('/hello/{name}')]
    public function hello(Request $request)
    {
        $name = $request->query->get('name');

        $title      =   sprintf('Hello %s', $name);
        $text       =   'This is an example';

        echo sprintf('<h1>%s</h1><p>%s</p>', $title, $text);
    }


    /**
     * Routing with typed params
     *
     * @param Request $request
     * @return void
     */
    #[Route(path: '/goodbye/{name:string}', methods: ['GET'])]
    public function goodbye(Request $request)
    {
        $name = $request->query->get('name');

        $title      =   sprintf('Goodbye %s', $name);
        $text       =   'This is an example';

        echo sprintf('<h1>%s</h1><p>%s</p>', $title, $text);
    }
}
