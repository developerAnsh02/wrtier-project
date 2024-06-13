<select name="countries" id="writer{{$id}}" multiple>   
     <option value="">Select Writer</option>
    @foreach($data['writer'] as $writer)
    <option value="{{$writer->id}}">{{$writer->name}}</option>
    @endforeach
</select>

<script>
    new MultiSelectTag('writer{{$id}}')  
</script>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/css/multi-select-tag.css">
<script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/js/multi-select-tag.js"></script>

