@extends('layouts.admin')
@section('style')
<style>
  .mapping-badge {
    font-size: 12px;
    padding: 4px 8px;
    background: #eef2f7;
    border-radius: 4px;
    font-family: monospace;
  }
</style>
@endsection
@section('content')
<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <h4 class="mb-0 font-size-18">Import Certificate Diamond Data</h4>
      </div>
      <div class="page-title-right">
        <a href="{{ route('admin.import.sample') }}" class="btn btn-outline-success btn-sm">
          <i class="mdi mdi-download me-1"></i> Download Sample Excel Template
        </a>
        <a href="{{ route('admin.return.index') }}?designation=3" class="btn btn-outline-primary btn-sm ms-1">
          <i class="mdi mdi-arrow-left me-1"></i> Go to Certificate Return
        </a>
      </div>
    </div>
  </div>
</div>
<!-- end page title -->

<div class="row">
  <div class="col-lg-6">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0 text-white"><i class="mdi mdi-file-excel me-1"></i> Upload Certificate File</h5>
      </div>
      <div class="card-body">

        @include('includes.flash_message')

        <form action="{{ route('admin.importPriceUpdate') }}" method="post" enctype="multipart/form-data">
          @csrf
          <div class="mb-4">
            <label for="import_file" class="form-label fw-bold">Select Excel / CSV File</label>
            <input type="file" name="import_file" id="import_file" class="form-control" accept=".xlsx,.xls,.csv" required>
            <div class="form-text">Supported formats: <code>.xlsx</code>, <code>.xls</code>, <code>.csv</code></div>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
              <i class="mdi mdi-upload me-1"></i> Import & Update Data
            </button>
            <a href="{{ route('admin.import.sample') }}" class="btn btn-success">
              <i class="mdi mdi-file-download-outline me-1"></i> Download Sample File
            </a>
          </div>
        </form>

      </div>
    </div>

    <div class="card shadow-sm mt-3">
      <div class="card-header bg-light">
        <h6 class="mb-0 fw-bold"><i class="mdi mdi-cog-outline me-1"></i> Automatic Default Values</h6>
      </div>
      <div class="card-body p-0">
        <table class="table table-bordered table-sm mb-0">
          <tbody>
            <tr>
              <th class="bg-light" style="width: 40%;">Certified Status</th>
              <td><code>Certified Diamond (is_non_certi = 0)</code></td>
            </tr>
            <tr>
              <th class="bg-light">Return Date</th>
              <td><code>Today (Current Date)</code></td>
            </tr>
            <tr>
              <th class="bg-light">Availability</th>
              <td><code>GA</code></td>
            </tr>
            <tr>
              <th class="bg-light">Lab</th>
              <td><code>IGI</code></td>
            </tr>
            <tr>
              <th class="bg-light">Certificate URL</th>
              <td><code>https://api.igi.org/viewpdf.php?r={Certificate #}</code></td>
            </tr>
            <tr>
              <th class="bg-light">Fluorescence / BGM</th>
              <td><code>NONE</code></td>
            </tr>
            <tr>
              <th class="bg-light">Heart & Arrows (H&A)</th>
              <td><code>No</code></td>
            </tr>
            <tr>
              <th class="bg-light">Location</th>
              <td><code>SURAT, GUJARAT, India</code></td>
            </tr>
            <tr>
              <th class="bg-light">Eye Clean / Growth</th>
              <td><code>Eye Clean: Yes | Growth Type: CVD</code></td>
            </tr>
            <tr>
              <th class="bg-light">Diamond Status</th>
              <td><code>purchased (Purchase record automatically created)</code></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card shadow-sm">
      <div class="card-header bg-light">
        <h6 class="mb-0 fw-bold"><i class="mdi mdi-table me-1"></i> Excel Column Mapping</h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
          <table class="table table-striped table-bordered table-sm mb-0">
            <thead class="table-light sticky-top">
              <tr>
                <th>#</th>
                <th>Excel Column</th>
                <th>Target Field</th>
                <th>Notes / Example</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td><span class="mapping-badge">Certificate #</span></td>
                <td><code>certi_no</code></td>
                <td>e.g. <code>LG856606837</code></td>
              </tr>
              <tr>
                <td>2</td>
                <td><span class="mapping-badge">Shape Name</span></td>
                <td><code>r_shape</code></td>
                <td>e.g. Oval Brilliant &rarr; <code>OVAL</code>, Cushion &rarr; <code>CUSHION</code></td>
              </tr>
              <tr>
                <td>3</td>
                <td><span class="mapping-badge">Weight</span></td>
                <td><code>return_weight</code></td>
                <td>Carat weight e.g. <code>4.51</code></td>
              </tr>
              <tr>
                <td>4</td>
                <td><span class="mapping-badge">Measurement1, 2, 3</span></td>
                <td><code>measurements</code></td>
                <td>Formatted as <code>9.2*9.09*5.97</code></td>
              </tr>
              <tr>
                <td>5</td>
                <td><span class="mapping-badge">Table</span></td>
                <td><code>table_percent</code></td>
                <td>Table % e.g. <code>69</code></td>
              </tr>
              <tr>
                <td>6</td>
                <td><span class="mapping-badge">Total Depth</span></td>
                <td><code>depth_percent</code></td>
                <td>Depth % e.g. <code>65.7</code></td>
              </tr>
              <tr>
                <td>7</td>
                <td><span class="mapping-badge">POL or Pol/Sym</span></td>
                <td><code>r_polish</code></td>
                <td>Excellent &rarr; <code>EX</code>, Very Good &rarr; <code>VG</code></td>
              </tr>
              <tr>
                <td>8</td>
                <td><span class="mapping-badge">SYM</span></td>
                <td><code>r_symmetry</code></td>
                <td>Excellent &rarr; <code>EX</code>, Very Good &rarr; <code>VG</code></td>
              </tr>
              <tr>
                <td>9</td>
                <td><span class="mapping-badge">CUT-PROP</span></td>
                <td><code>cut_grade</code></td>
                <td>e.g. <code>EX</code>, <code>VG</code>, <code>IDEAL</code> (optional)</td>
              </tr>
              <tr>
                <td>10</td>
                <td><span class="mapping-badge">Clarity</span></td>
                <td><code>r_clarity</code></td>
                <td>e.g. <code>VS 1</code> &rarr; <code>VS1</code>, <code>VVS 2</code> &rarr; <code>VVS2</code></td>
              </tr>
              <tr>
                <td>11</td>
                <td><span class="mapping-badge">Color (Long)</span></td>
                <td><code>r_color</code></td>
                <td>e.g. <code>E</code>, <code>F</code>, <code>D</code></td>
              </tr>
              <tr>
                <td>12</td>
                <td><span class="mapping-badge fw-bold text-primary">Stock #</span></td>
                <td><code>diamond_name</code></td>
                <td><strong>Unique Diamond Name</strong> (e.g. <code>OV-70A</code> / <code>OV-70-A</code>)</td>
              </tr>
              <tr>
                <td>13</td>
                <td><span class="mapping-badge">Price</span></td>
                <td><code>price</code> & <code>total_price</code></td>
                <td>Total Price = Weight &times; Price</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end row -->
@endsection
