
var exerciseRecommendations = {
    "underweight": [
        "Strength Training: Focus on compound exercises like squats, deadlifts, and bench presses to build muscle mass.",
        "Resistance Exercises: Use resistance bands or bodyweight exercises like push-ups, pull-ups, and lunges to strengthen muscles.",
        "Aerobic Exercises: Incorporate light cardio exercises like walking, swimming, or cycling to improve cardiovascular health without burning too many calories."
    ],
    "normalWeight": [
        "Cardiovascular Exercises: Engage in moderate-intensity aerobic activities such as brisk walking, jogging, cycling, or swimming for at least 30 minutes most days of the week.",
        "Strength Training: Include resistance training exercises using weights, resistance bands, or bodyweight exercises to maintain muscle strength and tone.",
        "Flexibility and Balance: Incorporate stretching exercises and activities like yoga or Pilates to improve flexibility, balance, and overall mobility."
    ],
    "overweight": [
        "Aerobic Workouts: Focus on cardiovascular exercises like running, cycling, brisk walking, or swimming to burn calories and improve heart health.",
        "Interval Training: Incorporate high-intensity interval training (HIIT) to boost metabolism and burn fat effectively.",
        "Strength Training: Combine strength training exercises with cardio to build lean muscle mass, increase metabolism, and aid in weight loss."
    ],
    "obese": [
        "Low-Impact Activities: Begin with low-impact exercises such as walking, water aerobics, or stationary cycling to reduce strain on joints and minimize injury risk.",
        "Gradual Progression: Start with short durations of exercise and gradually increase intensity and duration as fitness levels improve.",
        "Professional Guidance: Seek guidance from a fitness professional or healthcare provider for personalized exercise plans tailored to individual needs and limitations. Consider joining a supervised exercise program or working with a personal trainer to ensure safety and effectiveness."
    ]
};

// Retrieve exercise recommendations based on BMI group
function getExerciseRecommendations(bmi) {
    var bmiGroup;
    if (bmi < 18.5) {
        bmiGroup = "underweight";
    } else if (bmi >= 18.5 && bmi <= 24.9) {
        bmiGroup = "normalWeight";
    } else if (bmi >= 25 && bmi <= 29.9) {
        bmiGroup = "overweight";
    } else {
        bmiGroup = "obese";
    }
    return exerciseRecommendations[bmiGroup];
}

// Function to calculate BMI and display exercise recommendations
function calculateBMI() {
    var weight = parseFloat(document.getElementById("weight").value);
    var height = parseFloat(document.getElementById("height").value) / 100; // Convert height to meters

    var bmi = (weight / (height * height)).toFixed(1);

    var bmiResult = document.getElementById("bmiResult");

    var exerciseSuggestion = document.getElementById("exerciseSuggestion");

    bmiResult.textContent = "Your BMI is: " + bmi;

    var recommendations = getExerciseRecommendations(bmi);

    exerciseSuggestion.innerHTML = "<strong> Exercise Recommendations: </strong><br>";
    recommendations.forEach(function(recommendation, index) {
        exerciseSuggestion.innerHTML += "<li>" + recommendation + "</li>";
    });
}



function calculate(){
    var bmi
    var result = document.getElementById("result");

    // The value of the height slider
    var height = parseInt(document.getElementById("height").value);

    // The value of the weight slider
    var weight = parseInt(document.getElementById("weight").value);


    //The value of height and width should be displayed in the webpage using "textContent"
    document.getElementById("weight-val").textContent = weight + "kg";
    document.getElementById("height-val").textContent = height + "cm";

    //To calculate the BMI
    bmi = (weight/Math.pow((height/100),2)).toFixed(1);

    // With the help of  "textContent" we have arranged to display the result in this page.
    result.textContent = bmi;

    // BMI
    //All of the text is stored in "category"
    if(bmi < 18.5){
        category = "Underweight 😒 ";
        result.style.color = "blue" ;

    }

    else if(bmi >= 18.5 && bmi <= 24.9){
        category = " Normal Weight 😍";
        result.style.color = "green";
    }

    else if ( bmi >= 25 && bmi <= 29.9){
        category = "Overweight 😮";
        result.style.color = "violet";
    }


    //BMI is <=30
    else{
        category = "Obese 😱";
        result.style.color ="red";
    }

    //Now you have to make arrangements to display the information in the webpage with the help of "textContent"
    document.getElementById("category").textContent = category;

}