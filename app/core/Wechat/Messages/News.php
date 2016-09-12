<?php

namespace App\Wechat\Messages;

class News extends MessageBase
{
    public $type = 'news';

    public $news = array();

    public function __construct(array $news = array())
    {
        $this->addItem($news);
    }

    public function addItem($news)
    {
        if ($news instanceof NewsItem) {
            $this->_addItem($news);
        } elseif (array_key_exists('title', $news)) {
            $news = new NewsItem($news);
            $this->_addItem($news);
        } elseif (is_array($news)) {
            foreach ($news as $n) {
                $n = new NewsItem($n);
                $this->_addItem($n);
            }
        }

        return TRUE;
    }

    private function _addItem(NewsItem $news)
    {
        if ($news->validate())
            $this->news[] = $news;
    }

    public function outputXML($from, $to)
    {
        $newsLen = min(10, count($this->news));
        $articles = array();
        $count = 0;
        foreach ($this->news as $news) {
            if ($count >= 10)
                break;

            $articles[] = $news->toXML();
            $count += 1;
        }

        $msg = array(
            'ToUserName' => $to,
            'FromUserName' => $from,
            'MsgType' => $this->type,
            'ArticleCount' => $newsLen,
            'Articles' => $articles,
            'CreateTime' => TIMESTAMP,
        );
        return $this->XMLEncode($msg);
    }

    public function outputJson($to)
    {
        $articles = array();
        $count = 0;
        foreach ($this->news as $news) {
            if ($count >= 10)
                break;

            $articles[] = $news->toJson();
            $count += 1;
        }

        $msg = array(
            'touser' => $to,
            'msgtype' => $this->type,
            'news' => array(
                'articles' => $articles,
            ),
        );
        return self::formatJson($msg);
    }
}