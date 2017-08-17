<?php

namespace App\Demo\Command;

use App\Demo\Downloader\StreamDownloader;
use App\Demo\Exception\DownloaderException;
use App\Demo\Exception\MigrationException;
use DateTime;

use App\Demo\CsvReader\Reader;
use App\Demo\Transformer\CsvTransformer;
use App\Demo\CsvReader\Migration;

use App\Demo\Entity\Order;
use App\Demo\Repository\OrderRepository;

use App\Demo\Entity\Product;
use App\Demo\Repository\ProductRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DownloadFileCommand
 *
 * @package App\Demo\Command
 */
class DownloadFileCommand extends Command
{
    /**
     * Downloader
     *
     * @var StreamDownloader
     */
    protected $downloader;

    /**
     * DownloadFileCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('app:download-file');
    }

    /**
     * Configure detail for download file
     */
    protected function configure()
    {
        $this->setName('app:download-file')
            ->setDescription("Downloads the file from the url")
            ->setHelp(
                "This command allows you to download a file from the supplied url."
            );

        $this->addArgument(
            'source',
            InputArgument::REQUIRED,
            'The source url to download the file'
        );
    }

    /**
     * Execute downloading file , read file and import to database
     *
     * @param InputInterface  $input  Input from user
     * @param OutputInterface $output Output status to user
     *
     * @return bool
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) : bool {
        $output->writeln('Downloading file');
        $output->writeln("Source : " . $input->getArgument('source'));

        $destinationFile = sprintf('public/downloaded-%s.csv', rand());;

        //Start download to local and save
        $downloader = new StreamDownloader($input->getArgument('source'), $destinationFile);
        $downloader->begin();

        //Process to read file and insert into tables
        $migration  = new Migration(
            new Reader($destinationFile, new CsvTransformer),
            new OrderRepository(new Order()),
            new ProductRepository(new Product())
        );

        $result = $migration->process();
        $output->writeln("Total data Imported : " . $result);

        //Delete downloaded file
        $downloader->deleteDestinationFile();

        return true;
    }
}
