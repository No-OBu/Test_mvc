<?php
namespace Models;

class MessagesModel extends BaseModel
{
    protected $author;
    protected $date;
    protected $content;

    protected $authorModel;

    public function __construct($dataArray)
    {
        parent::__construct($dataArray);

        if (isset($dataArray['author'])) {
            $this->setAuthor(intval($dataArray['author']));
        }
        if (isset($dataArray['date'])) {
            if (is_string($dataArray['date'])) {
                $this->setDate(new \DateTime($dataArray['date']));
            } else {
                $this->setDate($dataArray['date']);
            }
        }
        if (isset($dataArray['content'])) {
            $this->setContent($dataArray['content']);
        }
    }

    public function setAuthor($author)
    {
        if (!is_int($author) || !($author > 0)) {
            throw new \Exception("Message.author must be a positive integer value", 500);
        }
        $this->author = $author;

        return $this;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setAuthorModel(UsersModel $authorModel)
    {
        $this->authorModel = $authorModel;

        return $this;
    }

    public function getAuthorModel()
    {
        return $this->authorModel;
    }
}
