<div class="ui fluid card">
    <div class="content">
      <h4 class="ui sub header">Attached Files</h4>
      <div class="ui small feed">
        @if($data->files->isEmpty())
          No files attached.
        @else
          @foreach($data->files as $file)
            <div class="event">
              <div class="content">
                <div class="summary" style="display:grid;">
                   <a href="{{ Storage::url($file->path) }}" target="_blank" class="pull-left">{{ $file->name }}</a>
                    <script id="previewImg" >
                       document.addEventListener("DOMContentLoaded", function(event) { 
                            var file = ("{{$file->path}}".split("."));
                            var supported_previews=["png","jpg","JPEG","jpeg","JPG","PNG","bmp","BMP"]
                            if(supported_previews.indexOf(file[file.length -1]) > -1){
                                console.log("YES");
                                var z = document.createElement('img');
                                z.src = "{{Storage::url($file->path) }}";
                                z.style  = "margin-top:5px";
                                document.getElementById("preview{{$file->path}}").appendChild(z);
                            }else{
                                console.log("NO");
                            }
                        });
                   </script>
                   <div id="preview{{$file->path}}" style="text-align: center;"></div>
                   @can('delete', $file)
                      {{ Form::open(['url' => url('file/delete', $file->id),'method'=>'DELETE' , 'class'=>'pull-right']) }}
                       <button type="submit" class="ui icon mini compact right floated red button">
                           <i class="trash icon"></i>
                       </button>
                     {{ Form::close() }}
                   @endcan
                </div>
              </div>
            </div>
          @endforeach
        @endif

      </div>
    </div>
    @can('update', $data)
      <div class="extra content text-center">
        <a class="ui mini button" onclick="$('#upload-modal').modal('show');">
          <i class="file pdf outline icon"></i>
          Upload PDF or Image
        </a>
      </div>
    @endcan
  </div>

  <div class="ui modal" id="upload-modal">
    <div class="header inline">Choose a file to upload!</div>
    <div class="content">
      {{ Form::open(['url' => url('upload', [$type,$data->id]), 'files' => true, 'class'=>'ui form', 'id' => 'upload-form']) }}
      <div class="text-center">
          {{ Form::file('file', ['id'=>'file','placeholder'=>'Choose a File']) }}
          <label for="file" class="ui icon large button" >
              <i class="file icon"></i>
              Choose a File
          </label>
      </div>
      <p class="text-muted text-center">PDF or image files only. Max file size is 5MB.</p>
    </div>
    <div class="actions">
        <div class="ui cancel button">Cancel</div>
        <button type="submit" form="upload-form" class="ui green approve button">
            <i class="icon check"></i>Submit
        </button>
      {{ Form::close() }}
    </div>
  </div>
