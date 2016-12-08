<?php

namespace Dhii\WpProvision\Model;

/**
 * Represents a WP theme.
 *
 * @since [*next-version*]
 */
class Theme implements ThemeInterface
{
    protected $name;
    protected $slug;
    protected $status;
    protected $version;
    protected $updateStatus;
    protected $author;

    public function __construct(array $data = [])
    {
        $this->_assignData($data);
    }

    protected function _assignData(array $data)
    {
        isset($data[self::K_NAME]) &&
            $this->_setName($data[self::K_NAME]);

        isset($data[self::K_SLUG]) &&
            $this->_setSlug($data[self::K_SLUG]);

        isset($data[self::K_STATUS]) &&
            $this->_setStatus($data[self::K_STATUS]);

        isset($data[self::K_VERSION]) &&
            $this->_setVersion($data[self::K_VERSION]);

        isset($data[self::K_UPDATES]) &&
            $this->_setUpdateStatus($data[self::K_UPDATES]);

        isset($data[self::K_AUTHOR]) &&
            $this->_setAuthor($data[self::K_AUTHOR]);

        return$this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getName()
    {
        return $this->name;
    }

    protected function _setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getSlug()
    {
        return $this->slug;
    }

    protected function _setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getStatus()
    {
        return $this->status;
    }

    protected function _setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getUpdateStatus()
    {
        return $this->updateStatus;
    }

    protected function _setUpdateStatus($updateStatus)
    {
        $this->updateStatus = $updateStatus;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getVersion()
    {
        return $this->version;
    }

    protected function _setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getAuthor()
    {
        return $this->author;
    }

    protected function _setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function isActive()
    {
        return in_array($this->getStatus(), [ThemeInterface::STATUS_ACTIVE], true);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function isInstalled()
    {
        return in_array($this->getStatus(), [ThemeInterface::STATUS_ACTIVE, ThemeInterface::STATUS_INACTIVE], true);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function isUpdateAvailable()
    {
        return in_array($this->getUpdateStatus(), [ThemeInterface::UPDATE_AVAILABLE], true);
    }
}
