<?php

    require_once 'vin.class.php';


    class vinBlanc extends Vin
    {
        private $type;

        public function __construct($type)
        {
            parent::__construct();
            $this->setType($type);
        }

    /**
     * Gets the value of type.
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the value of type.
     *
     * @param mixed $type the type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
    }
