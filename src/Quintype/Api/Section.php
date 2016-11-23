<?php

namespace Quintype\Api;

class Section
{
    public function getSectionDetails($sectionName, $allSections)
    {
        $cur_section_index = array_search($sectionName, array_column($allSections, 'slug'), true); /* Get the index of given section. */
        if ($cur_section_index !== false) { /* Given section found. */
          return $allSections[$cur_section_index]; /* Get details of given section. */
        } else { /* Given section not found. */
          return false;
        }
    }
}
