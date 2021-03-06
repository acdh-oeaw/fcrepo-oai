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
use acdhOeaw\oai\OaiException;

/**
 * Implements proper reporting of repository without sets.
 *
 * @author zozlak
 */
class NoSets extends SetInterface {

    /**
     * Reports no support for sets
     * @param string $resVar
     * @param string $set
     * @throws OaiException
     */
    public static function getSetFilter(string $resVar, string $set): string {
        throw new OaiException('noSetHierarchy');
    }

    /**
     * Reports no support for sets
     * @param string $resVar
     * @param string $setVar
     * @throws OaiException
     */
    public static function getSetClause(string $resVar, string $setVar): string {
        return '';
    }

    /**
     * Reports no support for sets
     * @param Fedora $fedora
     * @throws OaiException
     */
    public static function listSets(Fedora $fedora): array {
        throw new OaiException('noSetHierarchy');
    }

}
