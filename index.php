<?php
    include_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>

<?php
    $query = 'SELECT lga_id, lga_name, state_id FROM lga order by lga_name';
    $statement = $conn->prepare($query);
    $statement->execute();
    $fetch = $statement->fetchAll();
?>
    
    <div style="margin-left: 15px; margin-right: 15px;">
        <h2 style="text-align: center; margin-bottom: 20px;">Local Govt Result</h2>
        
        <div>
            <select name="lga" id="lga">
                <option vale="">Select Local Govt</option>
                <?php
                    foreach ($fetch as $data):
                ?>
                <option value="<?=$data['lga_id']; ?>" data-state="<?=$data['state_id']; ?>"><?=$data['lga_name']; ?></option>
                <?php
                    endforeach;
                ?>
            </select>

            <table>
                <thead>
                    <tr>
                        <th>Party Name</th>
                        <th>Poll Score</th>
                    </tr>
                </thead>

                <tbody id="poll-result">
                    <!-- <div id="poll-result">

                    </div> -->
                </tbody>
            </table>
        </div>
    </div>



</body>
<script>
    let lga = document.querySelector("#lga")

    lga.addEventListener('change', function(e){
        e.preventDefault();
        
        let lg_id = this.value
        let state_id = this.options[this.selectedIndex].getAttribute('data-state')

        document.querySelector("#poll-result").innerHTML = ''
        fetch('action/lga_result.php', {
            method: "POST",
            body: JSON.stringify({
                lg_id: lg_id,
                state_id: state_id,
                action: "get-lga-total-result"
            }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then( res => res.json())
        .then( data => {
            var tr;
            var total = 0;

            data.forEach(data => {
                tr += `
                    <tr>
                        <td>${(data.party_abbreviation == 'undefined')?'':data.party_abbreviation}</td>
                        <td>${(data.score == 'undefined')?'':data.score}</td>
                    </tr>
                `
                total += Number(data.score)
            });

            let totalValue = `<tr><td span="3">Total = ${total}</td></tr>`
            let pollResult = document.querySelector("#poll-result").innerHTML = tr + totalValue
        })
    })
</script>
</html>