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

namespace acdhOeaw\oai\metadata;

use DOMDocument;
use DOMElement;
use stdClass;
use acdhOeaw\fedora\FedoraResource;
use acdhOeaw\fedora\metadataQuery\SimpleQuery;
use acdhOeaw\oai\data\MetadataFormat;
use acdhOeaw\oai\OaiException;

/**
 * Creates &lt;metadata&gt; element by simply taking binary content of another
 * repository resource.
 * 
 * Of course it will work only if the target resource is an XML file satisfying 
 * requested OAI-PMH metadata schema (but checking it is out of scope of this 
 * class)
 *
 * Required metadata format definitition properties:
 * - `metaResProp` 
 * - `idProp`
 * so that SPARQL path `?res metaResProp / ^idProp ?metaRes` will fetch a correct
 * metadata resource.
 * 
 * @author zozlak
 */
class ResMetadata implements MetadataInterface {

    /**
     * Repository resouce storing actual metadata as its binary content.
     * @var \acdhOeaw\fedora\FedoraResource
     */
    private $metaRes;

    /**
     * Creates a metadata object for a given repository resource.
     * 
     * @param FedoraResource $resource repository resource object
     * @param stdClass $sparqlResultRow SPARQL search query result row 
     * @param MetadataFormat $format metadata format descriptor
     *   describing this resource
     */
    public function __construct(FedoraResource $resource,
                                stdClass $sparqlResultRow,
                                MetadataFormat $format) {
        $fedora        = $resource->getFedora();
        $this->metaRes = $fedora->getResourceByUri($sparqlResultRow->metaRes);
    }

    /**
     * Creates resource's XML metadata
     * 
     * @return DOMElement 
     * @throws \acdhOeaw\oai\OaiException
     */
    public function getXml(): DOMElement {
        $meta    = new DOMDocument();
        $success = $meta->loadXML((string) $this->metaRes->getContent()->getBody());
        if (!$success) {
            throw new OaiException('failed to parse given resource content as XML');
        }
        return $meta->documentElement;
    }

    /**
     * Returns a SPARQL search query part fetching additional data required by
     * the `__construct()` method.
     * 
     * In this case it is an URI of the repository resource storing the actual
     * metadata as its binary content.
     * 
     * @param MetadataFormat $format metadata format descriptor
     * @param string $resVar name of the SPARQL variable holding the repository
     *   resource URI
     * @return string
     * @see __construct()
     */
    static public function extendSearchQuery(MetadataFormat $format,
                                             string $resVar): string {
        $param = array(
            $format->metaResProp,
            $format->idProp
        );
        $query = new SimpleQuery($resVar . ' ?@ / ^?@ ?metaRes .', $param);
        return $query->getQuery();
    }

}
