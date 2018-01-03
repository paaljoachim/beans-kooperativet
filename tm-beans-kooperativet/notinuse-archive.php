<?php
/**
 * Example archive template.
 */
 
 

the_archive_description( '<div class="taxonomy-description">', '</div>' );
echo category_description();


// Load the document which is always needed at the bottom of template files.
beans_load_document();
