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

namespace acdhOeaw\oai\set;

use acdhOeaw\fedora\Fedora;

/**
 * Interface for OAI-PMH sets implementations.
 * 
 * It is a class because PHP doesn't consider static methods part of an
 * interface  therefore interface can't be used.
 * @author zozlak
 */
abstract class SetInterface {

    /**
     * Creates a part of the SPARQL search query filtring only resources
     * belonging to a given set.
     * @param string $resVar SPARQL variable denoting the resource URI
     * @param string $set setSpec value to be matched
     * @return string
     */
    abstract public static function getSetFilter(string $resVar, string $set): string;

    /**
     * Creates a part of the SPARQL search query fetching information on sets 
     * a resource belongs to.
     * @param string $resVar SPARQL variable denoting the resource URI
     * @param string $setVar SPARQL variable which should denoted the setSpec 
     *   value in the returned SPARQL query part
     * @return string
     */
    abstract public static function getSetClause(string $resVar, string $setVar): string;

    /**
     * Handles the `ListSets` OAI-PMH request.
     * @param Fedora $fedora repository connection object
     * @return array
     */
    abstract public static function listSets(Fedora $fedora): array;
}
