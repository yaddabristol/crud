<div class="tab-pane{{ (array_first($parent_array) === $current_item) ? ' active' : '' }}" id="#tab-{{ strtolower(preg_replace('#[^a-zA-Z0-9]+#', '-', $tab_name)) }}">
