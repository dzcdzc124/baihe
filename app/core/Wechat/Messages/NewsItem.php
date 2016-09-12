<?php

namespace App\Wechat\Messages;

class NewsItem
{
    private $title;
    private $description;
    private $url;
    private $picUrl;

    public function __construct($title = NULL, $description = NULL, $url = NULL, $picUrl = NULL)
    {
        $this->setNews($title, $description, $url, $picUrl);
    }

    public function setNews($title, $description = NULL, $url = NULL, $picUrl = NULL)
    {
        if (is_array($title)) {
            $this->title = array_get($title, 'title');
            $this->description = array_get($title, 'description');
            $this->url = array_get($title, 'url');
            $this->picUrl = array_get($title, 'picUrl');
        } else {
            $this->title = $title;
            $this->description = $description;
            $this->url = $url;
            $this->picUrl = $picUrl;
        }
    }

    public function validate()
    {
        return $this->title && $this->url;
    }

    public function toXML()
    {
        return array(
            'Title' => $this->title,
            'Description' => $this->description,
            'Url' => $this->url,
            'PicUrl' => $this->picUrl,
        );
    }

    public function toJson()
    {
        return array(
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'picurl' => $this->picUrl,
        );
    }
}