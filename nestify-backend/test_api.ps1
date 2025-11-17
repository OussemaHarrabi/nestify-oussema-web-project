$headers = @{
    "Content-Type" = "application/json"
}

$body = @{
    name = "Test Regular User"
    email = "test_regular_new@example.com"
    password = "password123"
    password_confirmation = "password123"
    user_type = "regular_user"
    phone = "21622334455"
    city = "Tunis"
} | ConvertTo-Json

Write-Host "=== Testing Registration ==="
try {
    $response = Invoke-RestMethod -Uri "http://localhost:8000/api/register" -Method POST -Headers $headers -Body $body
    Write-Host "✓ Registration successful!"
    Write-Host "Response: $($response | ConvertTo-Json -Depth 3)"
} catch {
    Write-Host "✗ Registration failed:"
    Write-Host "Error: $($_.Exception.Message)"
    if ($_.ErrorDetails) {
        Write-Host "Details: $($_.ErrorDetails.Message)"
    }
}

Write-Host "`n=== Testing Login ==="
$loginBody = @{
    email = "regular@test.com"
    password = "password123"
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/login" -Method POST -Headers $headers -Body $loginBody
    Write-Host "✓ Login successful!"
    Write-Host "User: $($loginResponse.user.name) ($($loginResponse.user.email))"
    Write-Host "User Type: $($loginResponse.user.user_type)"
    
    if ($loginResponse.access_token) {
        Write-Host "`n=== Testing Authentication ==="
        $authHeaders = @{
            "Authorization" = "Bearer $($loginResponse.access_token)"
            "Accept" = "application/json"
        }
        
        try {
            $userResponse = Invoke-RestMethod -Uri "http://localhost:8000/api/user" -Method GET -Headers $authHeaders
            Write-Host "✓ Authentication successful!"
            Write-Host "Authenticated user: $($userResponse.name) ($($userResponse.email))"
        } catch {
            Write-Host "✗ Authentication failed:"
            Write-Host "Error: $($_.Exception.Message)"
        }
    }
} catch {
    Write-Host "✗ Login failed:"
    Write-Host "Error: $($_.Exception.Message)"
    if ($_.ErrorDetails) {
        Write-Host "Details: $($_.ErrorDetails.Message)"
    }
}