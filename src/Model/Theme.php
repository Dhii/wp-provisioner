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

    /**
     * @since [*next-version*]
     *
     * @param mixed[] $data Any combination of the K_* keys.
     */
    public function __construct(array $data = [])
    {
        $this->_assignData($data);
    }

    /**
     * Looks for recognized data keys, and sets internal data members to corresponding values.
     *
     * @since [*next-version*]
     *
     * @param mixed[] $data Any combination of the K_* keys.
     *
     * @return Theme This instance.
     */
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

        return $this;
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

    /**
     * Sets the theme name.
     *
     * @since [*next-version*]
     *
     * @param string $name The name of the theme.
     *
     * @return Theme
     */
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

    /**
     * Sets the slug of the theme.
     *
     * @since [*next-version*]
     *
     * @param string $slug The theme slug.
     *
     * @return Theme This instance.
     */
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

    /**
     * Sets the theme status.
     *
     * @since [*next-version*]
     *
     * @param string $status The code of this theme's status.
     *
     * @return Theme This instance.
     */
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

    /**
     * Sets the theme's update status.
     *
     * @since [*next-version*]
     *
     * @param string $updateStatus The code of this theme's update status.
     *
     * @return Theme This instance.
     */
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

    /**
     * Sets this theme's version.
     *
     * @since [*next-version*]
     *
     * @param string $version The version number string.
     *
     * @return Theme This instance.
     */
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

    /**
     * Sets the theme's author.
     *
     * @since [*next-version*]
     *
     * @param string $author The author's name.
     *
     * @return Theme This instance.
     */
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
