<?php 

declare(strict_types= 1);

namespace App;

use App\Exceptions\ViewPathNotExist;

class View
{
    public function __construct(protected string $path, protected array $params = [])
    {

    }

    public function render()
    {
        $viewPath = VIEW_PATH . $this->path . '.php';

        if (!file_exists($viewPath)) 
        {
            throw new ViewPathNotExist();
        }

        foreach($this->params as $key => $value)
        {
            $$key = $value;
        }

        ob_start();
        include $viewPath;
        return ob_get_clean();
    }

    public static function make(string $path, array $params = [])
    {
        return new static($path, $params);
    }


    public function __toString()
    {
        return $this->render();
    }
    
}