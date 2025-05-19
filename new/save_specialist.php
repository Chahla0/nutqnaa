session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'parent') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$conn = new mysqli("localhost", "root", "", "speech_therapy");

$parent_id = $_SESSION['user_id'];
$specialist_id = (int)$_POST['specialist_id'];

// Check if relationship already exists
$check = $conn->prepare("SELECT id FROM parent_specialist WHERE parent_id = ? AND specialist_id = ?");
$check->bind_param("ii", $parent_id, $specialist_id);
$check->execute();

if ($check->get_result()->num_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Specialist already saved']);
} else {
    $insert = $conn->prepare("INSERT INTO parent_specialist (parent_id, specialist_id) VALUES (?, ?)");
    $insert->bind_param("ii", $parent_id, $specialist_id);
    if ($insert->execute()) {
        echo json_encode(['success' => true, 'message' => 'Specialist saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving specialist']);
    }
}