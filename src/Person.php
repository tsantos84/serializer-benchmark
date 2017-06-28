<?php

namespace TSantos\Benchmark;

/**
 * Class Person
 *
 * @author Tales Santos <tales.augusto.santos@gmail.com>
 */
class Person
{
    /** @var  integer */
    private $id;
    /** @var  string */
    private $name;
    /** @var  Person */
    private $mother;
    /** @var  boolean */
    private $married;

    /**
     * Person constructor.
     * @param int $id
     * @param string $name
     * @param bool $married
     * @param Person $mother
     */
    public function __construct(int $id, string $name, bool $married, Person $mother = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->mother = $mother;
        $this->married = $married;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Person
     */
    public function setId(int $id): Person
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Person
     */
    public function setName(string $name): Person
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Person
     */
    public function getMother(): ?Person
    {
        return $this->mother;
    }

    /**
     * @param Person $mother
     * @return Person
     */
    public function setMother(Person $mother): Person
    {
        $this->mother = $mother;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMarried(): bool
    {
        return $this->married;
    }

    /**
     * @param bool $married
     * @return Person
     */
    public function setMarried(bool $married): Person
    {
        $this->married = $married;
        return $this;
    }
}
