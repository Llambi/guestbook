<?php


namespace App\Messages;


class CommentMessage
{
    private $id;
    private $context;

    /**
     * CommentMessage constructor.
     * @param $id
     * @param $context
     */
    public function __construct(int $id, array $context = [])
    {
        $this->id = $id;
        $this->context = $context;
    }

    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getContext(): array
    {
        return $this->context;
    }


}