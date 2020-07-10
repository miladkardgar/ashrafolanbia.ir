<table class="">
        <tr>
                <th>ردیف</th>
                <th>نام و نام خانوادگی</th>
                <th>درصد پیشرفت اولیه</th>
                <th>روستا/آدرس</th>
                <th>دریافت مرحله اول</th>
                <th>دریافت مرحله دوم</th>
                <th>دریافت مرحله سوم</th>
                @foreach($invoices['titles'] as $title)
                <th>{{$title}}</th>
                @endforeach
                <th>نیاز به سرویس</th>
                <th>نیاز به حصار</th>
                <th>درصد پیشرفت</th>
                <th>تاریخ مصاحبه</th>
                <th>ساکن</th>
                <th>توضیحات</th>
                <th>جمع درصد مسکونی</th>
                <th>مشهد مقدس</th>
                <th>عتبات عالیات</th>
        </tr>


<?php $i=1 ?>
        @foreach($invoices['data'] as $province => $data)
        <tr >
                <td colspan="{{count($invoices['titles'])+16}}">{{$province}}</td>
        </tr>
                @foreach($data as $value)
                        <tr>
                                <td>{{$i}}</td>
                                <td>{{$value['name']}}</td>
                                <td>0</td>
                                <td>{{$value['address']}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                @foreach($invoices['titles'] as $items)
                                        <td>{{isset($value[0][$items]) ? $value[0][$items] : "-" }}</td>
                                @endforeach
                                <td></td>
                                <td></td>
                                <td>{{$value['percent']}}</td>
                                <td></td>
                                <td></td>
                                <td>{{$value['description']}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                        </tr>
                        <?php $i++ ?>
                @endforeach
        @endforeach

</table>
