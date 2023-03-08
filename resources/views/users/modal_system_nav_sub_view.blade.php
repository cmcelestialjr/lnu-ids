<table style="width:100%">
<tr>
    <th><center>Sub Nav</center></th>
    <th><center>Url</center></th>
    <th><center>Order</center></th>
    <th><center>Option</center></th>
</tr>
    @foreach($systems_nav_sub as $row)
    <tr>
        <td style="width:25%">
            <button class="btn btn-warning btn-warning-scan" style="width:100%">
                <span class="{{$row->icon}}"></span> {{$row->name}}</button></td>
        <td style="width:25%">
            <center>{{$row->url}}</center>
        </td>
        <td style="width:25%">
           <center>{{$row->order}}</center>
        </td>
        <td style="width:25%">
            <center><button class="btn btn-info btn-info-scan edit"
                        data-id="{{$row->id}}">
                <span class="fa fa-edit"></span> Edit</button></center>
        </td>
    </tr>
    @endforeach
</table>