<?php
/**
 * This file is part of the TSantos Serializer Bundle package.
 *
 * (c) Tales Santos <tales.augusto.santos@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TSantos\Benchmark;

/**
 * Class Post
 *
 * @author Tales Santos <tales.augusto.santos@gmail.com>
 */
class Post
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $summary;

    /**
     * Post constructor.
     * @param int $id
     * @param string $title
     * @param string $summary
     */
    public function __construct(int $id, string $title, string $summary)
    {
        $this->id = $id;
        $this->title = $title;
        $this->summary = $summary;
    }
}
