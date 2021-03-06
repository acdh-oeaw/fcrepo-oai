<?php

/**
 * The MIT License
 *
 * Copyright 2017 Austrian Centre for Digital Humanities.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace acdhOeaw\oai\deleted;

use acdhOeaw\fedora\metadataQuery\SimpleQuery;
use acdhOeaw\util\RepoConfig as RC;

/**
 * Implementation of the `acdhOeaw\oai\deleted\DeletedInterface` deriving
 * information on a resource deletion from existence of a given RDF triple
 * in the resource metadata.
 *
 * Required configuration properties:
 * - oaiDeletedRecord value to be reported in the `deletedRecord` field of the
 *   OAI-PMH `identify` response ("transient" or "persistent" - see
 *   https://www.openarchives.org/OAI/openarchivesprotocol.html#DeletedRecords)
 * - oaiDeletedProp - RDF property marking a resource as deleted
 * 
 * @author zozlak
 */
class RdfProperty extends DeletedInterface {

    /**
     * Returns the OAI-PMH `identify` response's `deletedRecord` value.
     * @return string
     */
    public static function getDeletedRecord(): string {
        return RC::get('oaiDeletedRecord');
    }

    /**
     * Creates a part of the SPARQL search query fetching if a resource is
     * deleted or not.
     * @param string $resVar SPARQL variable denoting the resource URI
     * @param string $delVar SPARQL variable which should denoted if the 
     *   resource is deleted or not - any non empty value will indicate it is
     *   deleted
     * @return string
     */
    public static function getDeletedClause(string $resVar, string $delVar): string {
        $param = array(RC::get('oaiDeletedProp'));
        $query = new SimpleQuery("$resVar ?@ $delVar .", $param);
        return $query->getQuery();
    }

}
