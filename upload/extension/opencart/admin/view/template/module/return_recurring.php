{{ header }}{{ column_left }}
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="float-end">
        <button type="submit" form="form-module" data-bs-toggle="tooltip" title="{{ button_save }}" class="btn btn-primary"><i class="fas fa-save"></i></button>
        <a href="{{ back }}" data-bs-toggle="tooltip" title="{{ button_back }}" class="btn btn-light"><i class="fas fa-reply"></i></a></div>
      <h1>{{ heading_title }}</h1>
      <ol class="breadcrumb">
        {% for breadcrumb in breadcrumbs %}
          <li class="breadcrumb-item"><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>
        {% endfor %}
      </ol>
    </div>
  </div>
  <div class="container-fluid">
    <div class="card">
      <div class="card-header"><i class="fas fa-pencil-alt"></i> {{ text_edit }}</div>
      <div class="card-body">
        <div class="alert alert-info">{{ text_info }}</div>
      </div>
    </div>
  </div>
</div>
{{ footer }}
