<?php
// src/AppBundle/Form/DataTransformer/IssueToNumberTransformer.php
namespace MU\ShareModule\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

use Zikula\UsersModule\Entity\RepositoryInterface\UserRepositoryInterface;

class StringObjectTransformer implements DataTransformerInterface
{
	/**
	 * 
	 * @var UserRepositoryInterface;
	 */
	protected $userRepository;
	

	/**
	 * 
	 * @param UserRepositoryInterface $userRepository
	 */
	public function __construct(UserRepositoryInterface $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	/**
	 * Transforms an object (issue) to a string (number).
	 *
	 * @param  recipient $recipient
	 * @return string
	 */
	public function transform($recipient)
	{
		if ($recipient == NULL) {
			return NULL;
		}

		return $recipient['uid'];
	}

	/**
	 * Transforms a string (number) to an object (user).
	 *
	 * @param  userid $userId
	 * @return Issue|null
	 * @throws TransformationFailedException if object (user) is not found.
	 */
	public function reverseTransform($userId)
	{
		// no user id
		if (!$userId) {
			return false;
		}

		$user = $this->userRepository->find($userId);

		if (null === $user) {
			// causes a validation error
			// this message is not shown to the user
			// see the invalid_message option
			throw new TransformationFailedException(sprintf(
					'An issue with user id "%s" does not exist!',
					$userId
					));
		}

		return $user;
	}
}