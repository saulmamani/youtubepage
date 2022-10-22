<?php

namespace App\Patterns\FactoryPattern;

class ContextData
{
    private IDataSource $strategy;

    public function __construct(IDataSource $strategy)
    {
        $this->strategy = $strategy;
    }

    public function setStrategy(IDataSource $strategy)
    {
        $this->strategy = $strategy;
    }

    public function getVideos()
    {
        return $this->strategy->search();
    }

    public function getComments($videoId)
    {
        return $this->getComments($videoId);
    }

}
