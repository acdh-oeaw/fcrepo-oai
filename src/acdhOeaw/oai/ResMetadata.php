<?php

/*
 * The MIT License
 *
 * Copyright 2017 zozlak.
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

namespace acdhOeaw\oai;

use DOMDocument;
use DOMElement;
use RuntimeException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Stream;

/**
 * Creates <metadata> element by simply taking content of a given resource.
 * 
 * Of course it will work only if a given resource is an XML file satisfying 
 * requested OAI-PMH metadata schema (but checking it is out of scope of this class)
 *
 * @author zozlak
 */
class ResMetadata extends Metadata {

    static private $client;

    /**
     * Reads data from a given URL without checking SSL certificates validity.
     * 
     * @param type $url 
     * @return \GuzzleHttp\Psr7\Stream
     */
    static private function getResource($url): Stream {
        if (self::$client === null) {
            self::$client = new Client(array('verify' => false));
        }
        $req = new Request('GET', $url);
        $resp = self::$client->send($req);
        return $resp->getBody();
    }

    protected function createDOM(DOMDocument $doc): DOMElement {
        $meta = new DOMDocument();
        // it would be more memory efficient to parse using DOMDocument::load()
        // but then it is impossible to turn off certificate check 
        $success = $meta->loadXML((string) self::getResource($this->res->getUri(true)));
        if (!$success) {
            throw new RuntimeException('failed to parse given resource content as XML');
        }
        $node = $doc->importNode($meta->documentElement, true);
        return $node;
    }

}
