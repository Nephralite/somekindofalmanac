<!DOCTYPE html>
<html lang="">
<body>

<div id="top_bar">
    <form action="/" method="get" id="form" style="display: flex">
        <?php
            //TODO - handle invalid inputs
            $chars = isset($_GET['q']) ? explode(' ', $_GET['q']) : null ;
            $json = json_decode(file_get_contents('roles.json'), true);
            for ($i=0; $i<=2; $i++) {
                if (is_null($chars) or $i >= count($chars) ) {
                    print '<div style="flex: 1"><input list="characters" id="input'.($i+1).'"><h4>no character selected<h4></div>';
                } else {
                    print '<div style="flex: 1"><input list="characters" id="input'.($i+1).'"value="'.$chars[$i].'"><h1 class="'.$json[$chars[$i]][3].'">'.$json[$chars[$i]][1].'</h1><h4>'.$json[$chars[$i]][4].'</h4></div>';
                }
            }
        ?>
        <!-- Hidden input for the combined value -->
        <input type="hidden" name="q" id="q">
    </form>
</div>
<div id="rulings">
    <?php
    $csv = str_getcsv(file_get_contents('interactions.csv'), "\n", "\"", "");
    foreach ($csv as $row) {
        $row =  str_getcsv($row, ",", "\"", "\\");
        if (is_null($chars) or count(array_intersect($chars, $row)) == count($chars)) {
            print '<div>';
            print '<h4>'.$json[$row[1]][1];
            if ($row[2]!='') {
                print ' & ' . $json[$row[2]][1];
                if ($row[3] != '') { print ' & ' . $json[$row[3]][1];}
            }
            if ($row[0]!='') { print ' ('.$row[0].')';}
            print '</h4><p>'.$row[4].'</p>';
            if ($row[5]!='') { print '<i>'.$row[5].'</i>'; }
            print '<br></div>';
        }
    }
    ?>
</div>
<datalist id="characters">
    <?php
        foreach ($json as $v) {
            print '<option value="'.$v[0].'">'.$v[1].'</option>';
        }
    ?>
</datalist>
<script>
    //take the inputs and move their values to q to prettify the url, and submit
    function custom_submit() {
        const val1 = document.getElementById('input1').value.trim();
        const val2 = document.getElementById('input2').value.trim();
        const val3 = document.getElementById('input3').value.trim();
        var output = `${val1} ${val2} ${val3}`
        document.getElementById('q').value = output.trim();
        document.getElementById('form').submit();
    }

    document.getElementById("input1").addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            custom_submit();
        }
    });
    document.getElementById("input2").addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            custom_submit();
        }
    });
    document.getElementById("input3").addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            custom_submit();
        }
    });
</script>
</body>
</html>
