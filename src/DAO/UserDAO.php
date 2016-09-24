<?php

namespace picobeauf\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use picobeauf\Domain\User;

class UserDAO extends DAO implements UserProviderInterface
{

	/**
     * {@inheritDoc}
     */
	public function loadUserByUsername($username)
	{
		$sql = "SELECT * FROM pico_comptes WHERE compte_login = ?";
		$row = $this->getDb()->fetchAssoc($sql, array($username));

		if ($row)
			return $this->buildDomainObject($row);
		else
			throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
	}

	/**
     * {@inheritDoc}
     */
	public function refreshUser(UserInterface $user)
	{
		$class = get_class($user);
		if (!$this->supportsClass($class)) {
			throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
		}
		return $this->loadUserByUsername($user->getUsername());
	}

	/**
     * {@inheritDoc}
     */
	public function supportsClass($class)
	{
		return 'picobeauf\Domain\User' === $class;
	}

	/**
     * Creates a User object based on a DB row.
     *
     * @param array $row The DB row containing User data.
     * @return \MicroCMS\Domain\User
     */
	protected function buildDomainObject($row) {
		$user = new User();
		$user->setId($row['compte_id']);
		$user->setUsername($row['compte_login']);
		$user->setPassword($row['compte_password']);
		$user->setSalt($row['compte_salt']);
		$user->setRole($row['compte_role']);
		return $user;
	}
}