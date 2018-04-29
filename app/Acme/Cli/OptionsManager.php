<?php
namespace Acme\Cli;

/**
 * Class used to manage and validate CLI options
 */
class OptionsManager
{
    /**
     * Constant containing supported short options
     */
    const SHORT_OPTS = 'g:t:';

    /**
     * Constant containing supported long options
     */
    const LONG_OPTS  = [ 'genre:', 'time:' ];

    /**
     * @var array Associated array of CLI arguments
     */
    protected $options;

    /**
     * @param array $options Associated array of CLI options as returned by getopt()
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Validate the supplied options array
     *
     * @throws \Exception
     */
    public function validate()
    {
        // If both g and genre exist it's invalid
        if (isset($this->options['g']) && isset($this->options['genre'])) {
            throw new \Exception('Duplicated param. Both -g and --genre supplied');
        }

        // Same for the existence of both t and --time
        if (isset($this->options['t']) && isset($this->options['time'])) {
            throw new \Exception('Duplicated param. Both -t and --time supplied');
        }

        // Any param being provided as an array is invalid - we only want one
        foreach ([ 'g', 't', 'genre', 'time' ] as $param) {
            if (isset($this->options[ $param ]) && is_array($this->options[ $param ])) {
                throw new \Exception("Duplicated param. $param provided multiple times");
            }
        }

        // Genre is a required field
        if (!$this->getGenre()) {
            throw new \Exception("Missing required param. Genre must be provided");
        }
    }

    /**
     * @return string|null Check the possible genre params and return the genre value if provided
     */
    public function getGenre()
    {
        if (isset($this->options['g'])) {
            return $this->options['g'];
        }

        if (isset($this->options['genre'])) {
            return $this->options['genre'];
        }

        return null;
    }

    /**
     * @return string|null Check the possible time params and return the time value if provided
     */
    public function getTime()
    {
        if (isset($this->options['t'])) {
            return $this->options['t'];
        }

        if (isset($this->options['time'])) {
            return $this->options['time'];
        }

        return null;
    }
}

