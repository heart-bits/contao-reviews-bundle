<?php

namespace Heartbits\ContaoReviews\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;

class ReviewMigration extends AbstractMigration
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function shouldRun(): bool
    {
        $schemaManager = $this->connection->getSchemaManager();

        // If the database table itself does not exist we should do nothing
        if (!$schemaManager->tablesExist(['tl_reviews'])) {
            return false;
        }

        $columns = $schemaManager->listTableColumns('tl_reviews');

        return
            isset($columns['author']) &&
            !isset($columns['pid']);
    }

    public function run(): MigrationResult
    {
        $this->connection->query('ALTER TABLE tl_reviews ADD pid int(10) unsigned NOT NULL default "1"');

        $stmt = $this->connection->prepare('UPDATE tl_reviews SET pid = author');

        $stmt->execute();

        return new MigrationResult(
            true,
            'Migrated reviews.'
        );
    }
}
