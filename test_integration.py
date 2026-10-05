"""
Comprehensive Quantum Integration Test Script
Tests all 15 Quantum Hybrid Neural Network, Diffusion, Attention, Hadamard, IBM Qiskit,
and High Intelligence AI modules.
"""

import sys
import os
import torch
import numpy as np

# Force UTF-8 stdout encoding for Windows console compatibility
if hasattr(sys.stdout, 'reconfigure'):
    sys.stdout.reconfigure(encoding='utf-8')

print("=" * 70)
print("COMPREHENSIVE QUANTUM INTEGRATION TEST SUITE")
print("=" * 70)

failures: list[str] = []

# 1. Custom quantum circuits
print("\n1. Testing quantum_circuit.py...")
try:
    from quantum_circuit import QuantumFeatureExtractor, QuantumNoiseGenerator, QuantumEncoder
    qfe = QuantumFeatureExtractor(n_qubits=4, n_layers=2)
    output = qfe(torch.randn(4))
    print(f"   [OK] QuantumFeatureExtractor working: {output.shape}")
except Exception as e:
    print(f"   [FAIL] quantum_circuit failed: {e}")
    failures.append(f"quantum_circuit: {e}")

# 2. Diffusion model
print("\n2. Testing diffusion_model.py...")
try:
    from diffusion_model import DiffusionProcess, SimpleDiffusionModel
    diffusion = DiffusionProcess(num_timesteps=50)
    model = SimpleDiffusionModel(in_channels=3, hidden_dim=32)
    loss = diffusion.p_losses(model, torch.randn(2, 3, 32, 32), torch.randint(0, 50, (2,)))
    print(f"   [OK] DiffusionProcess working: loss = {loss.item():.6f}")
except Exception as e:
    print(f"   [FAIL] diffusion_model failed: {e}")
    failures.append(f"diffusion_model: {e}")

# 3. Hybrid model
print("\n3. Testing hybrid_model.py...")
try:
    from hybrid_model import HybridDiffusionModel, QuantumGuidedDiffusion
    hybrid = HybridDiffusionModel(in_channels=3, hidden_dim=32, time_emb_dim=64, n_qubits=4)
    qg = QuantumGuidedDiffusion(in_channels=3, hidden_dim=32, time_emb_dim=64, n_qubits=4)
    h_out = hybrid(torch.randn(2, 3, 32, 32), torch.randint(0, 50, (2,)))
    qg_out = qg(torch.randn(2, 3, 32, 32), torch.randint(0, 50, (2,)))
    print(f"   [OK] HybridDiffusionModel & QuantumGuidedDiffusion working: {h_out.shape}, {qg_out.shape}")
except Exception as e:
    print(f"   [FAIL] hybrid_model failed: {e}")
    failures.append(f"hybrid_model: {e}")

# 4. Quantum Hadamard Engine
print("\n4. Testing quantum_hadamard.py...")
try:
    from quantum_hadamard import QuantumHadamardTransform, FastWalshHadamardTransform, QuantumHadamardAttention
    qht = QuantumHadamardTransform(n_qubits=4)
    qattn = QuantumHadamardAttention(embed_dim=64, num_heads=4, n_qubits=4)
    h_out, _ = qattn(torch.randn(2, 16, 64))
    print(f"   [OK] QuantumHadamardAttention working: {h_out.shape}")
except Exception as e:
    print(f"   [FAIL] quantum_hadamard failed: {e}")
    failures.append(f"quantum_hadamard: {e}")

# 5. IBM Quantum Qiskit Integration
print("\n5. Testing integrated_ibm_quantum.py...")
try:
    from integrated_ibm_quantum import IBMQuantumCNN, IBMQuantumLayer, IBMQuantumCircuitEngine
    ibm_cnn = IBMQuantumCNN(in_channels=3, num_classes=10, n_qubits=4)
    out = ibm_cnn(torch.randn(2, 3, 32, 32))
    print(f"   [OK] IBMQuantumCNN working: {out.shape}")
except Exception as e:
    print(f"   [FAIL] integrated_ibm_quantum failed: {e}")
    failures.append(f"integrated_ibm_quantum: {e}")

# 6. Ingenii Quantum Hybrid Networks
print("\n6. Testing integrated_quantum_networks.py...")
try:
    from integrated_quantum_networks import HybridQuantumCNN, IngeniiQuantumLayer
    ingenii_cnn = HybridQuantumCNN(in_channels=3, num_classes=10, n_qubits=4)
    out = ingenii_cnn(torch.randn(2, 3, 32, 32))
    print(f"   [OK] HybridQuantumCNN (Ingenii) working: {out.shape}")
except Exception as e:
    print(f"   [FAIL] integrated_quantum_networks failed: {e}")
    failures.append(f"integrated_quantum_networks: {e}")

# 7. NesyaLab Quantum Hybrid Diffusion
print("\n7. Testing integrated_diffusion_models.py...")
try:
    from integrated_diffusion_models import NesyaHybridDiffusion
    nesya = NesyaHybridDiffusion(in_channels=3, hidden_dim=32, n_qubits=4, num_timesteps=50)
    loss = nesya.training_loss(torch.randn(2, 3, 32, 32))
    print(f"   [OK] NesyaHybridDiffusion working: loss = {loss.item():.6f}")
except Exception as e:
    print(f"   [FAIL] integrated_diffusion_models failed: {e}")
    failures.append(f"integrated_diffusion_models: {e}")

# 8. D-Wave Quantum Annealing
print("\n8. Testing integrated_dwave.py...")
try:
    from integrated_dwave import DWaveHybridDiffusion
    dwave_m = DWaveHybridDiffusion(in_channels=3, hidden_dim=32, num_annealing_vars=16)
    out = dwave_m(torch.randn(2, 3, 32, 32))
    print(f"   [OK] DWaveHybridDiffusion working: {out.shape}")
except Exception as e:
    print(f"   [FAIL] integrated_dwave failed: {e}")
    failures.append(f"integrated_dwave: {e}")

# 9. TensorFlow Quantum Integration
print("\n9. Testing integrated_tf_quantum.py...")
try:
    from integrated_tf_quantum import TFQuantumCNN, TFQuantumGNN
    tfq_cnn = TFQuantumCNN(in_channels=3, num_classes=10, n_qubits=4)
    out = tfq_cnn(torch.randn(2, 3, 32, 32))
    print(f"   [OK] TFQuantumCNN working: {out.shape}")
except Exception as e:
    print(f"   [FAIL] integrated_tf_quantum failed: {e}")
    failures.append(f"integrated_tf_quantum: {e}")

# 10. Geometric Quantum Tensor
print("\n10. Testing integrated_geometric_tensor.py...")
try:
    from integrated_geometric_tensor import GeometricQuantumCircuit, QuantumGeometricTensor
    geom = GeometricQuantumCircuit(n_qubits=4, n_layers=2)
    out = geom(torch.randn(2, 4))
    print(f"   [OK] GeometricQuantumCircuit working: {out.shape}")
except Exception as e:
    print(f"   [FAIL] integrated_geometric_tensor failed: {e}")
    failures.append(f"integrated_geometric_tensor: {e}")

# 11. Quantum Neural Networks (QNN)
print("\n11. Testing integrated_quantum_nn.py...")
try:
    from integrated_quantum_nn import HybridQNN, QuanvolutionalNN
    qnn = HybridQNN(in_channels=1, num_classes=10, n_qubits=4)
    out = qnn(torch.randn(2, 1, 28, 28))
    print(f"   [OK] HybridQNN working: {out.shape}")
except Exception as e:
    print(f"   [FAIL] integrated_quantum_nn failed: {e}")
    failures.append(f"integrated_quantum_nn: {e}")

# 12. QC-CNN Inception
print("\n12. Testing integrated_qc_cnn.py...")
try:
    from integrated_qc_cnn import QCCNNInception
    qc_cnn = QCCNNInception(in_channels=1, num_classes=10, n_qubits=4)
    out = qc_cnn(torch.randn(2, 1, 14, 14))
    print(f"   [OK] QCCNNInception working: {out.shape}")
except Exception as e:
    print(f"   [FAIL] integrated_qc_cnn failed: {e}")
    failures.append(f"integrated_qc_cnn: {e}")

# 13. Quantum Transformer & ViT
print("\n13. Testing quantum_transformer.py...")
try:
    from quantum_transformer import QuantumTransformer, QuantumVisionTransformer
    vit = QuantumVisionTransformer(img_size=32, patch_size=8, in_channels=3, num_classes=10, embed_dim=64, n_qubits=4)
    out, _ = vit(torch.randn(2, 3, 32, 32))
    print(f"   [OK] QuantumVisionTransformer working: {out.shape}")
except Exception as e:
    print(f"   [FAIL] quantum_transformer failed: {e}")
    failures.append(f"quantum_transformer: {e}")

# 14. Quantum Meta-Learning (MAML)
print("\n14. Testing quantum_meta_learning.py...")
try:
    from quantum_meta_learning import QuantumMAML
    maml = QuantumMAML(input_dim=10, hidden_dim=32, output_dim=5, n_qubits=4)
    out = maml(torch.randn(2, 10))
    print(f"   [OK] QuantumMAML working: {out.shape}")
except Exception as e:
    print(f"   [FAIL] quantum_meta_learning failed: {e}")
    failures.append(f"quantum_meta_learning: {e}")

# 15. Quantum Reinforcement Learning & Federated
print("\n15. Testing quantum_reinforcement_learning.py & quantum_federated_learning.py...")
try:
    from quantum_reinforcement_learning import QuantumPPO
    from quantum_federated_learning import QuantumFederatedServer, QuantumClientModel
    ppo = QuantumPPO(state_dim=4, action_dim=2, hidden_dim=32, n_qubits=4)
    act, _, _ = ppo.get_action(torch.randn(1, 4))
    print(f"   [OK] QuantumPPO & QuantumFederatedServer working: action = {act.item()}")
except Exception as e:
    print(f"   [FAIL] RL / Federated failed: {e}")
    failures.append(f"RL / Federated: {e}")

# 16. Web App Initialization
print("\n16. Testing app.py Flask Initialization...")
try:
    from app import app, initialize_models
    initialize_models()
    print("   [OK] Flask App & All Model Registries initialized successfully!")
except Exception as e:
    print(f"   [FAIL] app.py initialization failed: {e}")
    failures.append(f"app.py initialization: {e}")

print("\n" + "=" * 70)
if failures:
    print(f"{len(failures)} QUANTUM INTEGRATION TEST(S) FAILED")
    for failure in failures:
        print(f" - {failure}")
    sys.exit(1)

print("ALL QUANTUM INTEGRATION TESTS COMPLETED SUCCESSFULLY")
print("=" * 70)
