<?php

	namespace App\Command;

	use App\Entity\User;
	use Doctrine\ORM\EntityManagerInterface;
	use Faker\Factory;
	use Symfony\Component\Console\Command\Command;
	use Symfony\Component\Console\Input\InputArgument;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Console\Question\ConfirmationQuestion;
	use Symfony\Component\Console\Style\SymfonyStyle;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

	class CreateAdminUserCommand extends Command
	{
		protected static $defaultName = 'App:CreateAdminUserCommand';
		private $entityManager;
		private $passwordEncoder;


		public function __construct(
			string $name = null,
			EntityManagerInterface $entityManager,
			UserPasswordEncoderInterface $passwordEncoder
		) {
			$this->entityManager = $entityManager;
			$this->passwordEncoder = $passwordEncoder;
			parent::__construct($name);
		}

		protected function configure()
		{
			$this
				->setDescription('this command create an admin user')
				->addArgument('email', InputArgument::REQUIRED, 'Admin user email')
				->addArgument('password', InputArgument::REQUIRED, 'Admin user password');
		}

		protected function execute(InputInterface $input, OutputInterface $output): int
		{

			$io = new SymfonyStyle($input, $output);

			$helper = $this->getHelper('question');
			$question = new ConfirmationQuestion(
				'Confirmer la création de l\'utilisateur?',
				false, '/^(y|j)/i');

			if (!$helper->ask($input, $output, $question)) {
				return 0;
			}

			$email = $input->getArgument('email');
			$password = $input->getArgument('password');
			$io->note(sprintf('User email: %s', $email));
			$io->note(sprintf('User password: %s', $password));


			$user = new User();
			$hashedPassword = $this->passwordEncoder->encodePassword($user, $password);
			$user->setEmail($email);
			$user->setPassword($hashedPassword);
			$user->setRoles(['ROLE_ADMIN']);

			try {
				$this->entityManager->persist($user);
				$this->entityManager->flush();
			} catch (\Exception $exception) {
				$io->error('A error occured : ' . $exception->getMessage());

				return 0;
			}

			$io->success('A new user has been created');

			return 0;
		}
	}