<?php
/**
 * Webkul Software.
 *
 * @category   Webkul
 * @package    Webkul_StoreOptimization
 * @author     Webkul
 * @copyright  Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */

namespace Webkul\StoreOptimization\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command class.
 */
class ImageCompress extends Command
{
    const TYPE_ARGUMENT = 'type';
    /**
     * @var \Webkul\StoreOptimization\Model\Converter\Adapter
     */
    protected $imageAdapter;

    /**
     * @var Magento\Framework\Filesystem\Io\File
     */
    protected $file;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $fileDriver;

    /**
     *
     * @param \Webkul\StoreOptimization\Model\Converter\Adapter $imageAdapter
     * @param \Magento\Framework\Filesystem\Io\File $file
     * @param \Magento\Framework\Filesystem\Driver\File $fileDriver
     */
    public function __construct(
        \Webkul\StoreOptimization\Model\Converter\Adapter $imageAdapter,
        \Magento\Framework\Filesystem\Io\File $file,
        \Magento\Framework\Filesystem\Driver\File $fileDriver
    ) {
        parent::__construct();
        $this->imageAdapter = $imageAdapter;
        $this->file = $file;
        $this->fileDriver = $fileDriver;
    }

    protected function configure()
    {
        $this->setName('image:compress');
        $this->setDescription('image:compress');
        $this->setDefinition([
            new InputArgument(
                self::TYPE_ARGUMENT,
                InputArgument::OPTIONAL,
                'compression type jpeg or webp'
            ),
            new InputOption(
                "path",
                '-p',
                InputArgument::OPTIONAL,
                'absolute path of the folder or image where image(s) need to be compressed'
            ),

        ]);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument(self::TYPE_ARGUMENT);
        $path = $input->getOption("path");
        
        if ($type == 'webp' || $type == 'jpeg') {
            
            if ($this->fileDriver->isDirectory($path)) {
                
                /**
                 * @phpcs:disable
                 */
                $files = scandir($path);
                /**
                 * @phpcs:enable
                 */
                foreach ($files as $file) {
                    $filePath = $path.DIRECTORY_SEPARATOR.$file;
                    $destinationPath = $this->getDestinationPath($filePath, $type);
                    if ($this->fileDriver->isFile($filePath)) {
                        $this->imageAdapter->convert(
                            $filePath,
                            $destinationPath,
                            [],
                            $type
                        );
                        $output->writeln("<info>converted image: ".$destinationPath." </info>");
                    }
                }
            } elseif ($this->fileDriver->isFile($path)) {
                $destinationPath = $this->getDestinationPath($path, $type);
                $this->imageAdapter->convert(
                    $path,
                    $destinationPath,
                    [],
                    $type
                );
                $output->writeln("<info>converted image: ".$destinationPath." </info>");
            } else {
                throw new \InvalidArgumentException('path is invalid');
            }
        } else {
            throw new \InvalidArgumentException('invalid compression type provided correct values are webp or jpeg');
        }
    }

    /**
     * get destinatio path
     *
     * @param string $path
     * @param string $type
     * @return string
     */
    public function getDestinationPath($path, $type)
    {
        $imagePathParts = $this->file->getPathInfo($path);
        return $imagePathParts['dirname']. DIRECTORY_SEPARATOR .$imagePathParts['filename'] .
        '.'.$type;
    }
}
