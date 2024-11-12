
<style>
   .loader {
  width: 88px;
  height: 88px;
  border: 4px solid;
  background: #e52f0540;
  border-color: transparent #8a1d03 #8a1d03 transparent;
  border-radius: 50%;
  display: inline-block;
  position: relative;
  box-sizing: border-box;
  animation: rotation 1s ease-in-out infinite;
}
.loader::after {
  content: '';  
  box-sizing: border-box;
  position: absolute;
  left: 50%;
  top: 50%;
  border: 12px solid;
  border-color: transparent #8a1d03 #8a1d03 transparent;
  transform: translate(-50%, -50%);
  border-radius: 50%;
}

@keyframes rotation {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
} 
    
    </style>
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="left-side-content">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Support Predictor</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="card" style="background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-left:40px; margin-right:40px;">
        <h1 class="mb-4" style="font-size: 28px; color: #333;">Member Support Predictor</h1>
        <p class="mb-4" style="font-size: 20px; color: #666;">Here is the AI-predicted list of the top ten members who need support more than others.</p>

        <!-- Loading message -->
       

        <!-- Table, initially hidden -->
        <div id="tableContainer" style="display: none;">
            <div class="table-responsive">
                <table class="table table-striped" style="background-color: #f9f9f9;">
                    <thead style="background-color: #8a1d03; color: white;">
                        <tr>
                            <th style="color: white; font-size:20px;">#</th>
                            <th style="color: white; font-size:20px;">First Name</th>
                            <th style="color: white; font-size:20px;">Last Name</th>
                            <th style="color: white; font-size:20px;">Phone</th>
                        </tr>
                    </thead>
                    <tbody id="combinedTableBody">
                        @foreach($members as $index => $member)
                            <tr>
                                <td style="font-size:18px;">{{ $index + 1 }}</td>
                                <td style="font-size:18px;">{{ $member->first_name }}</td>
                                <td style="font-size:18px;">{{ $member->last_name }}</td>
                                <td style="font-size:18px;">{{ $member->phone }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div id="loadingMessage" style="font-size: 18px; color: #666; text-align: center; margin:150px">
            <h2 style="color:#8a1d03;">AI Predicting...</h2>
        <span style="margin-top:20px" class="loader"></span>
            </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>

<script>
const members = @json($members);

async function predictSupport(tableData) {
    // Normalize data
    const normalizedData = tableData.map(d => ({
        income_per_month: d.income_per_month / Math.max(...tableData.map(t => t.income_per_month)),
        disability: d.disability === "Yes" ? 1 : 0,
        parent_status: d.parent_status === "Single" ? 1 : 0,
    }));

    // Prepare training data
    const trainingData = normalizedData.map(d => [d.income_per_month, d.disability, d.parent_status]);
    const labels = normalizedData.map(d => (d.income_per_month < 0.1 && d.disability === 1 && d.parent_status === 1) ? 1 : 0);

    // Define and compile model
    const model = tf.sequential();
    model.add(tf.layers.dense({ units: 16, activation: 'relu', inputShape: [3] }));
    model.add(tf.layers.dense({ units: 1, activation: 'sigmoid' }));
    model.compile({ optimizer: 'adam', loss: 'binaryCrossentropy', metrics: ['accuracy'] });

    // Train model
    const xs = tf.tensor2d(trainingData);
    const ys = tf.tensor2d(labels, [labels.length, 1]);
    await model.fit(xs, ys, { epochs: 200 });

    // Predict and sort results
    const predictions = model.predict(tf.tensor2d(normalizedData.map(d => [d.income_per_month, d.disability, d.parent_status]))).dataSync();
    const sortedData = tableData.map((d, index) => ({
        ...d,
        prediction: predictions[index]
    })).sort((a, b) => b.prediction - a.prediction);

    const top10Data = sortedData.slice(0, 10); // Select only the top 10

    // Populate table
    const tableBody = document.querySelector("#combinedTableBody");
    tableBody.innerHTML = ''; 

    top10Data.forEach((member, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td style="font-size:16px;">${index + 1}</td>
            <td style="font-size:16px;">${member.first_name}</td>
            <td style="font-size:16px;">${member.last_name}</td>
            <td style="font-size:16px;">${member.phone}</td>
        `;
        tableBody.appendChild(row);
    });

    // Hide loading message and show the table
    document.getElementById("loadingMessage").style.display = "none";
    document.getElementById("tableContainer").style.display = "block";
}

// Start the prediction process
predictSupport(members);
</script>
