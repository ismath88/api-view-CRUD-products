<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class UniqueProduct extends Constraint
{
    public $duplicateProductService = 'Record already exists.';
    /**
     * @var string notPermittedSeries.
     */
    public $notPermittedSeries = 'Overlapping validity dates are not permitted.';
    /**
     * @var string notPermittedSeriesParameter.
     */
    public $notPermittedSeriesParameter = 'Validity date(s) are not permitted.';

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
