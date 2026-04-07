const mode = process.argv[2] ?? "secure";
const baseUrl = process.env.TEST_BASE_URL ?? "http://localhost:8001";

async function main() {
  const response = await fetch(`${baseUrl}/api/services?file=../../etc/passwd`);
  const body = await response.text();

  console.log(`[Traversal][${mode}] status:`, response.status);

  if (mode === "secure") {
    if (response.status !== 403) {
      throw new Error(`Expected 403 in secure mode, got ${response.status}. Body: ${body}`);
    }
    return;
  }

  if (response.status === 403) {
    throw new Error("Vulnerable mode should allow the traversal payload.");
  }
}

main().catch((error) => {
  console.error(error);
  process.exit(1);
});
