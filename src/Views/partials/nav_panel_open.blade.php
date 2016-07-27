<div class="tab-pane fade{{ (reset($parent_array) === $current_item) ? ' in active' : '' }}" id="tab-{{ strtolower(preg_replace('#[^a-zA-Z0-9]+#', '-', $tab_name)) }}">
