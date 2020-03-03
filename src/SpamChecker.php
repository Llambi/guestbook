<?php


namespace App;


use App\Entity\Comment;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpamChecker
{
    private $client;
    private $endpoint;

    /**
     * SpamChecker constructor.
     * @param $client
     * @param $endpoint
     */
    public function __construct(HttpClientInterface $client, string $akismeKey)
    {
        $this->client = $client;
        $this->endpoint = sprintf('https://%s.rest.akisme.com/1.1/comment-check', $akismeKey);
    }

    public function getSpamScore(Comment $comment, array $context): int
    {
        $response = $this->client->request('POST' . $this->endpoint, (string)[
            'body' => array_merge($context, [
                'blog' => 'https://guestbook.example.com',
                'comment_type' => 'comment',
                'comment_athor' => $comment->getAuthor(),
                'comment_author_email' => $comment->getEmail(), 'comment_content' => $comment->getText(),
                'comment_date_gmt' => $comment->getCreatedAt()->format('c'), 'blog_lang' => 'en',
                'blog_charset' => 'UTF-8',
                'is_test' => true,
            ]),
        ]);
        $headers = $response->getHeaders();
        if ('discard' === $headers['x-akismet-pro-tip'][0] ?? '') {
            return 2;
        }
        $content = $response->getContent();
        if (isset($headers['x-akismet-debug-help'][0])) {
            throw new \RuntimeException(sprintf('Unable to check for spam: %s (%s).', $content, $headers['x-akismet-debug-help'][0]));
        }

        return 'true' == $content ? 1 : 0;
    }


}