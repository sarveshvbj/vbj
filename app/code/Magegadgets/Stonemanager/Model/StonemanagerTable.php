<?php
/**
 * MageGadgets Extensions
 * Stonemanager Extension
 * 
 * @package    		Magegadgets_Stonemanager
 * @copyright  		Copyright (c) 2017 MageGadgets Extensions (http://www.magegadgets.com/)
 * @contactEmail   	support@magegadgets.com
*/

namespace Magegadgets\Stonemanager\Model;

use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Exception\LocalizedException;
use Magegadgets\Stonemanager\Api\StonemanagerTableInterface;

class StonemanagerTable extends AbstractModel implements StonemanagerTableInterface
{
    protected $csv;
    protected $filesystem;
    protected $file;
	/**
     *   * @var \Magento\Framework\Filesystem\Directory\ReadFactory
     */
    private $readFactory;

    protected $_columns = ["id",'name', 'code', 'itemkey', 'itemcode', 'startcent','endcent','business_price','price','stone_type','status'];

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        Csv $csv,
        Filesystem $filesystem,
        File $file,
		\Magento\Framework\Filesystem\Directory\ReadFactory $readFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->csv = $csv;
        $this->filesystem = $filesystem;
        $this->file = $file;
		$this->readFactory = $readFactory;
    }

    protected function _construct()
    {
		$this->_init('Magegadgets\Stonemanager\Model\ResourceModel\StonemanagerTable');
    }

    /**
     * Get cash on delivery fee
     *
     * @param double $amount
     * @param string $country
     * @return double	
     */
    public function getFee($amount, $country)
    {
        return $this->_getResource()->getFee($amount, $country);
    }

    /**
     * Get table as array
     *
     * @return array
     */
    public function getTableAsArray()
    {
        return $this->_getResource()->getTableAsArray();
    }

    /**
     * Get table as CSV
     *
     * @return string
     */
    public function getTableAsCsv()
    {
        $data = $this->getTableAsArray();

        $tmpDir = $this->filesystem->getDirectoryWrite(DirectoryList::TMP);
        $fileName = $tmpDir->getAbsolutePath(uniqid(md5(time())).'.csv');

        $dataOut = [$this->_columns];
        foreach ($data as $row) {
            $dataOutRow = [];
            foreach ($this->_columns as $column) {
                if (($column == 'fee') && ($row['is_pct'])) {
                    $dataOutRow[] = $row[$column].'%';
                } else {
                    $dataOutRow[] = $row[$column];
                }
            }
            $dataOut[] = $dataOutRow;
        }

        $this->csv->saveData($fileName, $dataOut);

        $res = $this->file->fileGetContents($fileName);
        $this->file->deleteFile($fileName);

        return $res;
    }

    /**
     * Save from file
     *
     * @param string $fileName
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function saveFromFile($fileName)
    {
       //M2-20
        $tmpDirectory = ini_get('upload_tmp_dir')? $this->readFactory->create(ini_get('upload_tmp_dir'))
            : $this->filesystem->getDirectoryRead(DirectoryList::SYS_TMP);
        $path = $tmpDirectory->getRelativePath($fileName);
        $stream = $tmpDirectory->openFile($path);

        $headers = $stream->readCsv();
        if ($headers === false || count($headers) < 3) {
            $stream->close();
            throw new LocalizedException(__('Invalid columns count.'));
        }

        $columnsMap = array_flip($headers);

        $data = [];

        $rowNumber = 0;
        while (false !== ($csvLine = $stream->readCsv())) {
            if (empty($csvLine)) {
                continue;
            }

            $rowNumber++;

            $dataRow = [];

            // @codingStandardsIgnoreStart
            for ($i=0; $i<count($headers); $i++) {
            // @codingStandardsIgnoreEnd
                foreach ($this->_columns as $columnName) {

                    $value = $csvLine[$columnsMap[$columnName]];

                    $dataRow[$columnName] = $value;
                }
            }
			
            $data[] = $dataRow;
        }

        $this->_getResource()->populateFromArray($data);

        return $rowNumber;
    }

    /**
     * Get number of rows
     * @return int
     */
    public function getRowsCount()
    {
        return $this->_getResource()->getRowsCount();
    }
}
